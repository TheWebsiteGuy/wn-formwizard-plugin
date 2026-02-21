window.initFormBuilder = function (id) {
    if ($('#' + id + '-vue').length === 0) return;

    // Only init once
    if ($('#' + id + '-vue').hasClass('fb-initialized')) return;
    $('#' + id + '-vue').addClass('fb-initialized');

    const { createApp, ref, onMounted } = Vue;

    // Ensure vuedraggable is loaded
    const draggable = window.vuedraggable;

    const generateId = () => Math.random().toString(36).substr(2, 9);
    const toCamelCase = (str) => {
        return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function (word, index) {
            return index === 0 ? word.toLowerCase() : word.toUpperCase();
        }).replace(/\s+/g, '');
    };

    const app = createApp({
        components: {
            draggable
        },
        setup() {
            const sections = ref([]);
            const selectedField = ref(null);

            const toolboxFields = ref([
                { type: 'text', name: 'Text Input', icon: 'icon-font' },
                { type: 'email', name: 'Email Address', icon: 'icon-envelope' },
                { type: 'textarea', name: 'Text Area', icon: 'icon-align-left' },
                { type: 'number', name: 'Number', icon: 'icon-hashtag' },
                { type: 'select', name: 'Select/Dropdown', icon: 'icon-list-ul' },
                { type: 'radio', name: 'Radio Buttons', icon: 'icon-dot-circle-o' },
                { type: 'checkbox', name: 'Checkbox', icon: 'icon-check-square-o' },
                { type: 'file', name: 'File Upload', icon: 'icon-upload' }
            ]);

            // Load Initial Data
            onMounted(() => {
                const rawData = $('#' + id + '-data').html();
                try {
                    const parsed = JSON.parse(rawData);
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        sections.value = parsed.map(s => {
                            return {
                                ...s,
                                id: generateId(),
                                fields: (s.fields || []).map(f => ({ ...f, id: generateId() }))
                            };
                        });
                    }
                } catch (e) {
                    console.error('Failed to parse initial form builder data', e);
                }

                // If empty, add a default section
                if (sections.value.length === 0) {
                    addSection();
                }
            });

            const getIconForType = (type) => {
                const found = toolboxFields.value.find(t => t.type === type);
                return found ? found.icon : 'icon-code';
            };

            const addSection = () => {
                sections.value.push({
                    id: generateId(),
                    title: 'New Section',
                    columns: 1,
                    show_title: false,
                    fields: []
                });
                updateOutput();
            };

            const removeSection = (index) => {
                if (confirm('Are you sure you want to remove this entire section?')) {
                    sections.value.splice(index, 1);
                    selectedField.value = null;
                    updateOutput();
                }
            };

            const removeField = (sectionIndex, fieldIndex) => {
                sections.value[sectionIndex].fields.splice(fieldIndex, 1);
                selectedField.value = null;
                updateOutput();
            };

            const selectField = (field) => {
                selectedField.value = field;
            };

            const cloneField = (item) => {
                const nameNumber = Math.floor(Math.random() * 100);
                return {
                    id: generateId(),
                    label: item.name + ' ' + nameNumber,
                    code: 'field' + nameNumber,
                    type: item.type,
                    span: 'full',
                    required: false,
                    placeholder: '',
                    options: '',
                    validation_rules: '',
                    cssClass: 'form-control',
                    wrapperClass: 'form-group'
                };
            };

            const autoGenerateCode = (field) => {
                if (!field.code || field.code.startsWith('field')) {
                    field.code = toCamelCase(field.label);
                    updateOutput();
                }
            };

            const updateOutput = () => {
                // Remove internal 'id' before saving
                const cleanData = JSON.parse(JSON.stringify(sections.value));
                cleanData.forEach(s => {
                    delete s.id;
                    if (s.fields) {
                        s.fields.forEach(f => delete f.id);
                    }
                });

                $('#' + id + '-input').val(JSON.stringify(cleanData));
            };

            return {
                sections,
                toolboxFields,
                selectedField,
                getIconForType,
                addSection,
                removeSection,
                removeField,
                selectField,
                cloneField,
                autoGenerateCode,
                updateOutput
            };
        }
    });

    app.mount('#' + id + '-vue');
};
