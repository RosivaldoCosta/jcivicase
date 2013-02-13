using System;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using System.Reflection;


namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class IntakeFormTranslator : EntityMapperTranslator<BusinessEntitiesAlias.Intake, DataWebServiceAlias.Intake>
    {
         
       
        public override bool CanTranslate(Type targetType, Type sourceType)
        {
            //if( sourceType.BaseType is Form)
            return base.CanTranslate(targetType, sourceType);
        }

        protected override DataWebServiceAlias.Intake BusinessToService(IEntityTranslatorService service, Intake value)
        {
            DataWebServiceAlias.Intake f = new DataWebServiceAlias.Intake();
            foreach (PropertyInfo source in value.GetType().GetProperties())
            {
                foreach (PropertyInfo target in f.GetType().GetProperties())
                {
                    if (target.Name.Equals(source.Name))
                    {
                        object o = source.GetValue(value, null);

                        target.GetSetMethod().Invoke(f, new object[] { o });
                    }
                }
            }

            return f;
        
        }

        protected override Intake ServiceToBusiness(IEntityTranslatorService service, DataWebServiceAlias.Intake value)
        {
            Intake form = new Intake();
            form.CaseNumber = value.CaseNumber;

            foreach (PropertyInfo p in form.GetType().GetProperties())
            {
                //p.SetValue(value, p.GetValue(f1, null), null);
                PropertyInfo source = value.GetType().GetProperty(p.Name);
                if(source != null)
                    p.GetSetMethod().Invoke(form, new object[] { source.GetValue(value,null) });
                
            }

            return form;
        }
    }
}
